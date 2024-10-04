#!/bin/bash

# Lấy địa chỉ IP của máy
IP_ADDRESS=$(hostname -I | awk '{print $1}')

# Kiểm tra xem địa chỉ IP đã được lấy thành công chưa
if [ -z "$IP_ADDRESS" ]; then
    echo "Không thể lấy địa chỉ IP."
    exit 1
fi

# Chạy ứng dụng Laravel trên địa chỉ IP và cổng 8000
echo "Chạy ứng dụng Laravel tại http://$IP_ADDRESS:8001"
php artisan serve --host="$IP_ADDRESS" --port=8001
