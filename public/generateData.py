import json
import random
import sys

import joblib
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.neighbors import NearestNeighbors
from sklearn.preprocessing import LabelEncoder


def create_csv():
    user_ids = range(1, 101)
    product_ids = range(1, 100)
    actions = ['view', 'purchased', 'addcart']
    categories = ['thiet-bi-ve-sinh', 'xe-dien', 'may-loc-nuoc', 'tam-op-tuong']
    age_List = ['3-17', '18-35', '36-55', '55+']

    # Danh sách tên sản phẩm trong lĩnh vực điện máy
    electronics_products = [
        'Tivi LG 55 inch', 'Tủ lạnh Samsung 300L', 'Máy giặt Electrolux 9kg',
        'Lò vi sóng Panasonic', 'Điều hòa Daikin 12000 BTU', 'Máy tính xách tay Dell',
        'Máy ảnh Canon EOS', 'Loa bluetooth JBL', 'Smartphone Xiaomi', 'Máy lọc không khí Sharp'
    ]

    # Danh sách giá cho từng sản phẩm (giả định)
    electronics_prices = [
        15000000, 10000000, 8000000, 5000000, 12000000, 20000000, 25000000, 3000000, 7000000, 6000000
    ]

    data = {
        'user_id': [],
        'product_id': [],
        'action': [],
        'category': [],
        'age': [],
        'product_name': [],  # Thêm cột cho tên sản phẩm
        'price': [],         # Thêm cột cho giá sản phẩm
        'sale': []           # Thêm cột cho giá giảm
    }

    for _ in range(2000):
        user_id = random.choice(user_ids)
        product_id = random.choice(product_ids)
        action = random.choice(actions)
        category = random.choice(categories)
        age = random.choice(age_List)
        
        # Chọn ngẫu nhiên một tên sản phẩm và giá từ danh sách
        product_name = random.choice(electronics_products)
        price = electronics_prices[electronics_products.index(product_name)]  # Lấy giá tương ứng với sản phẩm
        
        # Tính toán giá giảm ngẫu nhiên (giảm tối đa 60%)
        sale_percentage = random.uniform(0, 0.6)  # Tạo số ngẫu nhiên trong khoảng từ 0 đến 0.6
        sale_value = round(price * sale_percentage)  # Giá trị giảm
        sale = f"{int(sale_percentage * 100)}"  # Định dạng phần trăm

        data['user_id'].append(user_id)
        data['product_id'].append(product_id)
        data['action'].append(action)
        data['category'].append(category)
        data['age'].append(age)
        data['product_name'].append(product_name)  # Thêm tên sản phẩm vào dữ liệu
        data['price'].append(price)      # Định dạng giá sản phẩm
        data['sale'].append(sale)                    # Thêm giá giảm vào dữ liệu

    df = pd.DataFrame(data)
    df.to_csv('user_actions.csv', index=False, encoding='utf-8')

create_csv()
