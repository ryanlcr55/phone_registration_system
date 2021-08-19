## Intro
該系統提供的工能如下
1. 讓店家申請 店家代號
2. 讓民眾傳送並紀錄 電話 店家代號與時間, 以供疫調
3. 提供疫調人員輸入確診者電話號碼與時間, 取得與確診者在七天內 ( 天數範圍可調  ) 進入的商店的其他客戶, 且該名單內的客戶資料為 確診者在進入商店到往後推七天內的所有紀錄

## Setup
1. 安裝docker compose
2. 在專案內執行以下指令
3. 使用 創建商店時使用地址自動帶入座標功能 ( geocoding ) 時, 請在 .env 內的 `GOOGLE_GEOCODING_API_KEY` 新增 google api key

```
$ chmod 550 setup.sh
$ ./setup.sh
```
 

## Test
```
   $docker-compose exec app php artisan test
```
#### notice: 若未填入` create store with address api` 測試無法通過


## API
[Document](https://phoneregistrationsystem.docs.apiary.io/#introduction/response-code)
