<?php 
namespace App\Services;

use App\Client\config;
use App\Reponsitories\UserReponsitory;

class UserService{

    protected $user;
     
    protected $config;
    public function __construct(UserReponsitory $userReponsitory, config $config)
    {
        $this->user = $userReponsitory;

        $this->config = $config;
    }


    public function search_user($data){
        $user = $this->user->search_user($data);
        
        $html = array_map(function($item) {
            return $this->config->getUserTable('user', $item);
        }, $user);

        return response()->json(['success' => true, 'html' => $html]);
    }


    public function getUser($page){
        $user = $this->user->PerPage($page);
        $html = array_map(function($item) {
            return $this->config->getUserTable('user', $item);
        },$user->items());

        return response()->json(['success' => true, 
            'html' => $html,
            'total' => $user->total(),
            'last_page' => $user->lastPage(),
        ]);
    }


    public function selectUser($page){
        $data = $this->user->selectUser($page);
        return response()->json([
            'data' => $data->items(),
            'total' => $data->total(),
            'last_page' => $data->lastPage(),
        ]);


    }


    /**
     * function handle get address(provines, district, ward) where api ghn
     *
     * @param array $request
     * @return void
     */
    public function handleAddress(array $request): void 
    {

        auth()->user()->getAddress()->update(['active' => 0]);
        $provincesData = $this->HandleData($request['provinces']);
        $districtData = $this->HandleData($request['district']);
        $wardData = $this->HandleData($request['ward']);
        $homeNumber = $request['home_number'];
        $this->user->Address($provincesData['id'],$provincesData['name'], $districtData['id'], $districtData['name'], $wardData['id'], $wardData['name'], $homeNumber);
    }

    private function HandleData($data){
        $parts = explode('|', $data);
        $id = $parts[0];
        $name = $parts[1];

        return [
            'id' => $id,
            'name' => $name,
        ];
    }
    

    /**
     * update 
     *
     * @param integer $startPrice
     * @param integer $endPrice
     * @param integer $sort_price
     * @param integer $sort_favourite
     * @param integer $sort_sale
     * @return boolean
     */
    public function updateUserFilter($startPrice, $endPrice, $sort_price, $sort_favourite, $sort_sale): bool
    {
        $user = auth()->user();

        // Create an array to hold the updated attributes
        $attributes = [];

        // Compare current user attributes with provided values and update if necessary
        if ($user->start_price != $startPrice) {
            $attributes['start_price'] = $startPrice; 
        }

        if ($user->end_price != $endPrice) {
            $attributes['end_price'] = $endPrice; 
        }

        if ($user->sort_price != $sort_price) {
            $attributes['sort_price'] = $sort_price; 
        }

        if ($user->sort_favourite != $sort_favourite) {
            $attributes['sort_favourite'] = $sort_favourite; 
        }

        if ($user->sort_sale != $sort_sale) {
            $attributes['sort_sale'] = $sort_sale; 
        }


        
        // Update the user model if there are any changes
        if (!empty($attributes)) {
            try {
                $user->update($attributes); // Update the user model
                return true; // Return true indicating successful update
            } catch (\Exception $e) {
                // Handle any exceptions (e.g., database errors)
                \Log::error('Error updating user filters: ' . $e->getMessage());
                return false; // Return false indicating update failure
            }
        }

        return true; // Return true if no updates were needed
    }


    /**
     * edit information user 
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editInformation(array $data): \Illuminate\Http\JsonResponse
    {

        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $currentData = [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];

        $normalizedData = array_change_key_case($data, CASE_LOWER);
        $normalizedCurrentData = array_change_key_case($currentData, CASE_LOWER);

        if ($normalizedData !== $normalizedCurrentData) {
            $user->first_name = $data['firstName'] ?? $user->first_name;
            $user->last_name = $data['lastName'] ?? $user->last_name;
            $user->email = $data['email'] ?? $user->email;
            $user->phone = $data['phone'] ?? $user->phone;
            $user->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => true, 'message' => 'No changes detected']);
    }

}