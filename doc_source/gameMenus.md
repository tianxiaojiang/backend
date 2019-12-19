# 菜单列表

接口地址： ``` /authentication/system-menu/show-menus ```

请求方式：
```
GET
```

### 请求参数
| 参数名 | 参数类型 | 是否必须 | 说明 |
| --- | --- | --- | --- |
| sid | int | 是 | 系统id |
| game_id | string | 是 | 游戏id |
| callback | string | 否 | 如果客户端使用jsonp的方式跨域调用，需要传此js会到处理函数名 |
| Authorization | string | 否 | 如果客户端使用jsonp的方式跨域调用，传此参数，就不需要header头里的Authorization |


### 请求header
| 参数名 | 参数类型 | 是否必须 | 说明 |
| --- | --- | --- | --- |
| Authorization | string | 是 | 访问token |

> 如果直接使用html调用此接口，需要把header```Authorization```移到请求参数里去。有跨域限制。

响应结果：

| 字段名 | 字段类型 | 字段说明 |
| --- | --- | --- |
| sm_id | int | 菜单id |
| sm_label | string | 菜单名 |
| sm_view | string | 前端对应地址 |
| sm_parent_id | string | 父id(已排好层级关系，无需关心) |
| sm_set_or_business | string | 业务菜单还是管理菜单(这里都是业务菜单，无需关心) |
| sort_num | int | 排序号，已弃用。结果已在后台排好序 |
| list | array | 子菜单 |

结果示例：
```
{
    "code": 0,
    "msg": "",
    "data": {
        "5": {
            "sm_id": "6",
            "sm_label": "基本设置",
            "sm_view": "set",
            "sm_parent_id": "0",
            "sm_set_or_business": "0",
            "sort_num": 5,
            "list": [
                {
                    "sm_id": "8",
                    "sm_label": "资源分类",
                    "sm_view": "source_type",
                    "sm_parent_id": "6",
                    "sm_set_or_business": "0",
                    "sort_num": 7,
                    "title": "资源分类",
                    "name": "source_type",
                    "icon": ""
                }
            ],
            "title": "基本设置",
            "name": "set",
            "icon": ""
        },
        "9": {
            "sm_id": "10",
            "sm_label": "美术",
            "sm_view": "art",
            "sm_parent_id": "0",
            "sm_set_or_business": "0",
            "sort_num": 9,
            "list": [
                {
                    "sm_id": "7",
                    "sm_label": "美术项目管理",
                    "sm_view": "art_project",
                    "sm_parent_id": "10",
                    "sm_set_or_business": "0",
                    "sort_num": 6,
                    "title": "美术项目管理",
                    "name": "art_project",
                    "icon": ""
                },
                {
                    "sm_id": "9",
                    "sm_label": "用户管理",
                    "sm_view": "user",
                    "sm_parent_id": "10",
                    "sm_set_or_business": "0",
                    "sort_num": 8,
                    "title": "用户管理",
                    "name": "user",
                    "icon": ""
                },
                {
                    "sm_id": "11",
                    "sm_label": "美术资源包管理",
                    "sm_view": "packet",
                    "sm_parent_id": "10",
                    "sm_set_or_business": "0",
                    "sort_num": 10,
                    "title": "美术资源包管理",
                    "name": "packet",
                    "icon": ""
                },
                {
                    "sm_id": "12",
                    "sm_label": "反馈管理",
                    "sm_view": "feedback",
                    "sm_parent_id": "10",
                    "sm_set_or_business": "0",
                    "sort_num": 11,
                    "title": "反馈管理",
                    "name": "feedback",
                    "icon": ""
                }
            ],
            "title": "美术",
            "name": "art",
            "icon": ""
        }
    }
}
```


