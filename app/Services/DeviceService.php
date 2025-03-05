<?php 
namespace App\Services;

use App\Reponsitories\DeviceReponsitory;

class DeviceService{


    protected $deviceReponsitory;

    public function __construct(DeviceReponsitory $deviceReponsitory)
    {
        $this->deviceReponsitory =$deviceReponsitory;
    }

    public function handleAdd(int $user_id = null, string $subcription_id) : bool
    {
        // Kiểm tra xem device đã tồn tại với subcription_id đã cho chưa
        $device = $this->deviceReponsitory->selectUserID($subcription_id);

        if (!$device) {
            // Nếu device chưa tồn tại, thêm mới với user_id có thể là null
            $this->deviceReponsitory->add($user_id, $subcription_id);
            return true;
        }

        // Nếu device đã tồn tại
        if ($device->user_id === null && $user_id != null) {
            // Nếu device đã tồn tại nhưng chưa có user_id, cập nhật user_id mới
            $this->deviceReponsitory->updateUser_id($user_id, $device);
        } elseif ($device->user_id !== $user_id) {
            // Nếu device đã có user_id nhưng không khớp với user_id hiện tại, cập nhật user_id
            $this->deviceReponsitory->updateUser_id($user_id, $device);
        } elseif ($device->user_id === $user_id && $device->subcription_id !== $subcription_id) {
            // Nếu device đã có user_id và subcription_id không khớp, thêm mới
            $this->deviceReponsitory->add($user_id, $subcription_id);
            
        } elseif ($device->subcription_id == $subcription_id && $device->user_id == $user_id) {
            
            return true;
        } else {
            // Các trường hợp khác, huỷ bỏ (cancel)
            return false;
        }

        // Nếu có bất kỳ cập nhật nào đã được thực hiện, trả về true
        return true;
    }

    
}

