# coffee
爱上咖啡厅出纳系统


# API

### 0x01

* api/image

添加文件

#### get

* image
    - 文件类型

#### return

```
{
    "status": "success",
    "id": [
        "2",
        "3"
    ]
}

{
    "status": "error",
    "errMsg": "file type error or other error",
    "id": []
}
```

### 0x02

* api/purchase

表单数据上传

#### GET

```
NATURE:早can             类别   
NAME:包子                名字
TOTAL_PRICE:12.5         总价
TOTAL_NUM:123            总数
DESCRIBE:hhh,我买的早can  描述
PURCHASE_TIME:123456789  购买时间
IMAGE_IDS:[1,2,3]        图片id
```


#### return

```
{
    "status": "success",
    "purchase_id": "7"
}

{
    "status": "error",
    "errMsg": "some value is empty"
}
```


