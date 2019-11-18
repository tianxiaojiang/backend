# 获取token

接口地址： ``` /open/token/gain ```

| 参数名 | 参数类型 | 是否必须 | 说明 |
| --- | --- | --- | --- |
| code | string | 是 | 临时凭证 |
| sid | int | 是 | 系统id |

响应结果：

| 字段名 | 字段类型 | 字段说明 |
| --- | --- | --- |
| access_token | string | 访问token |

结果示例：
```
{
  "code": 0,
  "msg": "成功",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjBlMTI1NTFhOWE0NDgyMGIxMGVkZmIzOGM3NTJkMTNmIn0.eyJpc3MiOiJpbnRlZ3JhdGlvbl9iYWNrZ3JvdW5kIiwiZXhwIjoxNTc0NjY1MjM0LCJuYmYiOjE1NzQwNjA0MzQsImlhdCI6MTU3NDA2MDQzNCwidWlkIjozMiwibmFtZSI6Ilx1NzUzMFx1NTM2Ylx1NmMxMSIsInJvbGVfaW5mbyI6IntcIjFcIjpbLTFdfSIsInNpZCI6MTIsInN5c3RlbV9uYW1lIjoiXHU2NTg3XHU0ZWY2XHU2ZDQxXHU4ZjZjIiwic3RhZmZfbnVtYmVyIjoxNzIzNSwiYWNjb3VudCI6InRpYW53ZWltaW4iLCJqdGkiOiIwZTEyNTUxYTlhNDQ4MjBiMTBlZGZiMzhjNzUyZDEzZiJ9.657Nb0T2NAG6W95IuoJIa7KyZp8Db-Uv4m8PxPWvtRM"
  }
}
```


