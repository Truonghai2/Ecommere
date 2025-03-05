import json
import os
import sys

import numpy as np
import pandas as pd
import torch
import torch.nn as nn
import torch.optim as optim
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder


class NCF(nn.Module):
    def __init__(self, num_users, num_items, embedding_dim=50, layers=[64, 32, 16]):
        super(NCF, self).__init__()
        
        self.user_embedding = nn.Embedding(num_users, embedding_dim)
        self.item_embedding = nn.Embedding(num_items, embedding_dim)
        
        self.mlp_layers = nn.ModuleList()
        input_size = embedding_dim * 2
        for layer_size in layers:
            self.mlp_layers.append(nn.Linear(input_size, layer_size))
            self.mlp_layers.append(nn.ReLU())
            input_size = layer_size
        
        self.prediction_layer = nn.Linear(layers[-1], 1)
        self.sigmoid = nn.Sigmoid()
    
    def forward(self, user, item):
        user_embed = self.user_embedding(user)
        item_embed = self.item_embedding(item)
        
        x = torch.cat([user_embed, item_embed], dim=-1)
        
        for layer in self.mlp_layers:
            x = layer(x)
        
        prediction = self.sigmoid(self.prediction_layer(x))
        return prediction.squeeze()

def prepare_data(df):
    user_encoder = LabelEncoder()
    item_encoder = LabelEncoder()
    
    df['user_id'] = user_encoder.fit_transform(df['user_id'])
    df['item_id'] = item_encoder.fit_transform(df['product_id'])

    df['action'] = df['action'].apply(
        lambda x: 1.0 if x == 'purchase' else (0.3 if x == 'addCart' else 0.6)
    )

    return df, user_encoder, item_encoder

def recommend_items_with_scores(model, user_id, user_encoder, item_encoder, top_k=10):
    model.eval()
    
    try:
        encoded_user = user_encoder.transform([user_id])[0]
    except ValueError:
        # Xử lý khi user_id không tồn tại
        print(f"User ID {user_id} not found in training data. Using default recommendations.")
        return recommend_popular_items(item_encoder, model, top_k=top_k)

    # Lấy danh sách tất cả items
    all_items = np.arange(len(item_encoder.classes_))
    
    with torch.no_grad():
        user_tensor = torch.tensor([encoded_user] * len(all_items), dtype=torch.long)
        item_tensor = torch.tensor(all_items, dtype=torch.long)
        predictions = model(user_tensor, item_tensor).numpy()
    
    # Sắp xếp kết quả theo điểm dự đoán
    top_indices = predictions.argsort()[-top_k:][::-1]
    top_items = item_encoder.inverse_transform(top_indices)
    top_scores = predictions[top_indices]
    
    return list(zip(top_items, top_scores))

def recommend_popular_items(item_encoder, model, top_k=10):
    # Giả định rằng mô hình có thể dự đoán cho tất cả các items
    all_items = np.arange(len(item_encoder.classes_))
    
    with torch.no_grad():
        # Tạo một "user giả định" (ID = 0)
        user_tensor = torch.tensor([0] * len(all_items), dtype=torch.long)
        item_tensor = torch.tensor(all_items, dtype=torch.long)
        predictions = model(user_tensor, item_tensor).numpy()
    
    # Sắp xếp sản phẩm dựa trên dự đoán
    top_indices = predictions.argsort()[-top_k:][::-1]
    top_items = item_encoder.inverse_transform(top_indices)
    top_scores = predictions[top_indices]
    
    return list(zip(top_items, top_scores))

def calculate_content_similarity(target_product_id, products_df, top_k=10):
    tfidf = TfidfVectorizer()
    product_names = products_df['title'].fillna('')
    tfidf_matrix = tfidf.fit_transform(product_names)
    
    try:
        target_index = products_df[products_df['id'] == target_product_id].index[0]
    except IndexError:
        return []
    
    cosine_similarities = cosine_similarity(tfidf_matrix[target_index], tfidf_matrix).flatten()
    top_indices = cosine_similarities.argsort()[-top_k-1:-1][::-1]
    
    return [(products_df.iloc[i]['id'], cosine_similarities[i]) for i in top_indices]

def hybrid_recommendations(user_id, model, user_encoder, item_encoder, products_df, alpha=0.7, top_k=10):
    cf_results = recommend_items_with_scores(model, user_id, user_encoder, item_encoder, top_k=top_k)
    hybrid_scores = {}
    
    for product_id, cf_score in cf_results:
        cbf_results = calculate_content_similarity(product_id, products_df, top_k=5)
        
        for similar_product_id, cbf_score in cbf_results:
            if similar_product_id not in hybrid_scores:
                hybrid_scores[similar_product_id] = 0
            hybrid_scores[similar_product_id] += alpha * cf_score + (1 - alpha) * cbf_score
    
    sorted_hybrid_scores = sorted(hybrid_scores.items(), key=lambda x: x[1], reverse=True)
    return [product_id for product_id, _ in sorted_hybrid_scores]

def train_ncf(df, test_size=0.2, epochs=50, lr=0.001, model_path='ncf_model.pth'):
    df, user_encoder, item_encoder = prepare_data(df)
    
    if len(df) <= 1:
        print("Dataset is too small to split into train and test sets. Using all data for training.")
        train_df = df
        test_df = pd.DataFrame()
    else:
        train_df, test_df = train_test_split(df, test_size=test_size)
    
    num_users = df['user_id'].nunique()
    num_items = df['item_id'].nunique()
    
    model = NCF(num_users, num_items)
    
    # Tải mô hình đã lưu (nếu có)
    if os.path.exists(model_path):
        try:
            saved_state = torch.load(model_path)
            
            # Kiểm tra kích thước của các lớp nhúng
            saved_user_embedding_size = saved_state['user_embedding.weight'].size(0)
            saved_item_embedding_size = saved_state['item_embedding.weight'].size(0)
            
            if saved_user_embedding_size != num_users or saved_item_embedding_size != num_items:
                print("Mismatch in embedding sizes. Adjusting model to match saved state.")
                model = NCF(saved_user_embedding_size, saved_item_embedding_size)
            
            model.load_state_dict(saved_state)
            model.eval()
            print(f"Model loaded from {model_path}")
        except RuntimeError as e:
            print(f"Error loading model: {e}. Initializing new model.")
    
    criterion = nn.BCELoss()
    optimizer = optim.Adam(model.parameters(), lr=lr)
    
    for epoch in range(epochs):
        model.train()
        total_loss = 0
        
        for _, row in train_df.iterrows():
            user = torch.tensor(row['user_id'], dtype=torch.long)
            item = torch.tensor(row['item_id'], dtype=torch.long)
            action = torch.tensor(row['action'], dtype=torch.float)
            
            optimizer.zero_grad()
            prediction = model(user, item)
            loss = criterion(prediction, action)
            
            loss.backward()
            optimizer.step()
            
            total_loss += loss.item()
        
        print(f'Epoch {epoch+1}, Loss: {total_loss/len(train_df) if len(train_df) > 0 else 0}')
    
    torch.save(model.state_dict(), model_path)

    return model, user_encoder, item_encoder

def get_recommendations(user_id, products_df):
    user_log_path = f'public/dataAI/user_log_{user_id}.json'
    try:
        df = pd.read_json(user_log_path, encoding='utf-8')
    except ValueError:
        print(f"Error: Could not load data from {user_log_path}. Ensure the file exists and is correctly formatted.")
        return

    if 'action' not in df.columns:
        print("Action column is missing!")
        return

    # Train or load the model
    model, user_encoder, item_encoder = train_ncf(df)

    # Collaborative Filtering Recommendations
    cf_results = recommend_items_with_scores(model, user_id, user_encoder, item_encoder, top_k=10)
    if not cf_results:
        print(f"No recommendations available for user {user_id} due to unseen label.")
        return

    # Hybrid Recommendations
    recommendations = hybrid_recommendations(user_id, model, user_encoder, item_encoder, products_df)

    print(f"Recommended items for user {user_id}:")
    recommendations_data = []

    for product_id in recommendations:
        product_info = products_df[products_df['id'] == product_id]
        if not product_info.empty:
            recommendations_data.append({"product_id": int(product_id)})
            print(f"- Product ID: {product_id}")

    # Save recommendations to a JSON file
    output_path = f'public/dataAI/output_{user_id}.json'
    try:
        with open(output_path, 'w', encoding='utf-8') as f:
            json.dump(recommendations_data, f, ensure_ascii=False, indent=4)
        print(f"Recommendations saved to {output_path}")
    except Exception as e:
        print(f"Error: Failed to save recommendations to {output_path}. {e}")

        
products_path = f'public/dataAI/products.json'
products_df = pd.read_json(products_path, encoding='utf-8')

if len(sys.argv) < 2:
    print("Usage: python run.py <user_id>")
    sys.exit(1)

user_id = int(sys.argv[1])
get_recommendations(user_id, products_df)
