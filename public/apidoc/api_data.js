define({ "api": [
  {
    "type": "put",
    "url": "/api/settings",
    "title": "Update user's settings",
    "name": "Update_user_s_settings",
    "group": "Settings",
    "version": "0.1.0",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Accept",
            "description": "<p>application/json</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Content-Type",
            "description": "<p>application/x-www-form-urlencoded</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "deviceId",
            "description": "<p>deviceId</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Body fields": [
          {
            "group": "Body fields",
            "type": "String",
            "optional": false,
            "field": "tempUnit",
            "description": "<p>Temperature unit ENUM : [c, f]</p>"
          },
          {
            "group": "Body fields",
            "type": "String",
            "optional": false,
            "field": "windSpeedUnit",
            "description": "<p>Wind speed unit ENUM : [kph, mph, m/s]</p>"
          },
          {
            "group": "Body fields",
            "type": "Integer",
            "optional": false,
            "field": "minTemp",
            "description": "<p>Require with maxTemp min:-100 max:100 Less than maxTemp</p>"
          },
          {
            "group": "Body fields",
            "type": "Integer",
            "optional": false,
            "field": "maxTemp",
            "description": "<p>Require with minTemp min:-100 max:100 Greater than minTemp</p>"
          },
          {
            "group": "Body fields",
            "type": "String",
            "optional": false,
            "field": "timezone",
            "description": "<p>Timezone (format - Europe/Kiev)</p>"
          },
          {
            "group": "Body fields",
            "type": "Array",
            "optional": false,
            "field": "locations",
            "description": "<p>Multidimensional array locations[n][country] locations[n][place]</p>"
          },
          {
            "group": "Body fields",
            "type": "Array",
            "optional": false,
            "field": "disasterCategories",
            "description": "<p>Array of strings (abbreviations of events)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"success\": true,\n    \"code\": 0,\n    \"locale\": \"en\",\n    \"message\": \"OK\",\n    \"data\": null\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "DeviceNotFound",
            "description": "<p>Device not found</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidRequestData",
            "description": "<p>Invalid Request Data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Device not found:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"success\": false,\n    \"code\": 1,\n    \"locale\": \"en\",\n    \"message\": \"Error #1\",\n    \"data\": null\n}",
          "type": "json"
        },
        {
          "title": "Invalid Request Data:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"success\": false,\n    \"code\": 15,\n    \"locale\": \"en\",\n    \"message\": \"The given data was invalid.\",\n    \"data\": {\n        \"messages\": {\n            \"minTemp\": [\n                \"The min temperature must be greater than max temperature.\"\n            ]\n        }\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "docs/API/Settings.coffee",
    "groupTitle": "Settings"
  },
  {
    "type": "post",
    "url": "/api/user",
    "title": "Create user",
    "name": "Create_user",
    "group": "User",
    "version": "0.1.0",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Accept",
            "description": "<p>application/json</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Content-Type",
            "description": "<p>application/x-www-form-urlencoded</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Body fields": [
          {
            "group": "Body fields",
            "type": "String",
            "optional": false,
            "field": "deviceId",
            "description": "<p>Required device id max-length:255</p>"
          },
          {
            "group": "Body fields",
            "type": "String",
            "optional": false,
            "field": "pushToken",
            "description": "<p>Push token max-length:255</p>"
          },
          {
            "group": "Body fields",
            "type": "String",
            "optional": false,
            "field": "receipt",
            "description": "<p>Receipt base_64 encoded max-length:65000</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"success\": true,\n    \"code\": 0,\n    \"locale\": \"en\",\n    \"message\": \"OK\",\n    \"data\": null\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidRequestData",
            "description": "<p>Invalid Request Data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Invalid Request Data:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"success\": false,\n    \"code\": 15,\n    \"locale\": \"en\",\n    \"message\": \"The given data was invalid.\",\n    \"data\": {\n        \"messages\": {\n            \"deviceId\": [\n                \"The device id field is required.\"\n            ]\n        }\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "docs/API/User.coffee",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "/api/user",
    "title": "Get all user's data",
    "name": "Get_all_user_s_data",
    "group": "User",
    "version": "0.1.0",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Accept",
            "description": "<p>application/json</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Content-Type",
            "description": "<p>application/x-www-form-urlencoded</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "deviceId",
            "description": "<p>deviceId</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"success\": true,\n    \"code\": 0,\n    \"locale\": \"en\",\n    \"message\": \"OK\",\n    \"data\": {\n        \"expirationDate\": null,\n        \"settings\": {\n            \"tempUnit\": \"c\",\n            \"windSpeedUnit\": \"kph\",\n            \"minTemp\": -10,\n            \"maxTemp\": 20,\n            \"timezone\": \"Africa/Abidjan\",\n            \"disasterCategories\": [\n                \"BH\",\n                \"CC\"\n            ]\n        },\n        \"locations\": [\n            {\n                \"country\": \"Albania\",\n                \"place\": \"Berat\"\n            },\n            {\n                \"country\": \"Ukraine\",\n                \"place\": \"Kharkiv\"\n            }\n        ]\n    }\n}",
          "type": "json"
        },
        {
          "title": "Success-Response with empty settings:",
          "content": "HTTP/1.1 200 OK\n  {\n      \"success\": true,\n      \"code\": 0,\n      \"locale\": \"en\",\n      \"message\": \"OK\",\n      \"data\": {\n          \"expirationDate\": null,\n          \"settings\": null,\n          \"locations\": []\n      }\n  }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "DeviceNotFound",
            "description": "<p>Device not found</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Device not found:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"success\": false,\n    \"code\": 1,\n    \"locale\": \"en\",\n    \"message\": \"Error #1\",\n    \"data\": null\n}",
          "type": "json"
        }
      ]
    },
    "filename": "docs/API/User.coffee",
    "groupTitle": "User"
  },
  {
    "type": "put",
    "url": "/api/user",
    "title": "Update user's data",
    "name": "Update_user_s_data",
    "group": "User",
    "version": "0.1.0",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Accept",
            "description": "<p>application/json</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Content-Type",
            "description": "<p>application/x-www-form-urlencoded</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "deviceId",
            "description": "<p>deviceId</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Body fields": [
          {
            "group": "Body fields",
            "type": "String",
            "optional": false,
            "field": "deviceId",
            "description": "<p>Device id max-length:255</p>"
          },
          {
            "group": "Body fields",
            "type": "String",
            "optional": false,
            "field": "pushToken",
            "description": "<p>Push token max-length:255</p>"
          },
          {
            "group": "Body fields",
            "type": "String",
            "optional": false,
            "field": "receipt",
            "description": "<p>Receipt base_64 decoded max-length:65000</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"success\": true,\n    \"code\": 0,\n    \"locale\": \"en\",\n    \"message\": \"OK\",\n    \"data\": null\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "DeviceNotFound",
            "description": "<p>Device not found</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidRequestData",
            "description": "<p>Invalid Request Data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Device not found:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"success\": false,\n    \"code\": 1,\n    \"locale\": \"en\",\n    \"message\": \"Error #1\",\n    \"data\": null\n}",
          "type": "json"
        },
        {
          "title": "Invalid Request Data:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"success\": false,\n    \"code\": 15,\n    \"locale\": \"en\",\n    \"message\": \"The given data was invalid.\",\n    \"data\": {\n        \"messages\": {\n            \"deviceId\": [\n                \"The device id field is required.\"\n            ]\n        }\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "docs/API/User.coffee",
    "groupTitle": "User"
  }
] });
