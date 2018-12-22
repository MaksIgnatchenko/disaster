###
@api {get} /api/user Get all user's data
@apiName Get all user's data
@apiGroup User
@apiVersion 0.1.0

@apiHeader {String} Accept application/json
@apiHeader {String} Content-Type application/x-www-form-urlencoded
@apiHeader {String} deviceId deviceId

@apiSuccessExample Success-Response:
    HTTP/1.1 200 OK
    {
        "success": true,
        "code": 0,
        "locale": "en",
        "message": "OK",
        "data": {
            "expirationDate": null,
            "settings": {
                "tempUnit": "c",
                "windSpeedUnit": "kph",
                "minTemp": -10,
                "maxTemp": 20,
                "timezone": "Africa/Abidjan",
                "disasterCategories": [
                    "BH",
                    "CC"
                ]
            },
            "locations": [
                {
                    "country": "Albania",
                    "place": "Berat"
                },
                {
                    "country": "Ukraine",
                    "place": "Kharkiv"
                }
            ]
        }
    }

@apiSuccessExample Success-Response with empty settings:
  HTTP/1.1 200 OK
    {
        "success": true,
        "code": 0,
        "locale": "en",
        "message": "OK",
        "data": {
            "expirationDate": null,
            "settings": null,
            "locations": []
        }
    }

@apiError DeviceNotFound Device not found
@apiErrorExample Device not found:
      HTTP/1.1 400 Bad Request
      {
          "success": false,
          "code": 1,
          "locale": "en",
          "message": "Error #1",
          "data": null
      }
###

###
@api {post} /api/user Create user
@apiName Create user
@apiGroup User
@apiVersion 0.1.0

@apiHeader {String} Accept application/json
@apiHeader {String} Content-Type application/x-www-form-urlencoded

@apiParam (Body fields) {String} deviceId Required device id max-length:255
@apiParam (Body fields) {String} pushToken Push token max-length:255
@apiParam (Body fields) {String} receipt Receipt base_64 encoded max-length:65000

@apiSuccessExample Success-Response:
    HTTP/1.1 200 OK
    {
        "success": true,
        "code": 0,
        "locale": "en",
        "message": "OK",
        "data": null
    }

@apiError InvalidRequestData Invalid Request Data
@apiErrorExample Invalid Request Data:
    HTTP/1.1 400 Bad Request
    {
        "success": false,
        "code": 15,
        "locale": "en",
        "message": "The given data was invalid.",
        "data": {
            "messages": {
                "deviceId": [
                    "The device id field is required."
                ]
            }
        }
    }
###

###
@api {put} /api/user Update user's data
@apiName Update user's data
@apiGroup User
@apiVersion 0.1.0

@apiHeader {String} Accept application/json
@apiHeader {String} Content-Type application/x-www-form-urlencoded
@apiHeader {String} deviceId deviceId

@apiParam (Body fields) {String} deviceId Device id max-length:255
@apiParam (Body fields) {String} pushToken Push token max-length:255
@apiParam (Body fields) {String} receipt Receipt base_64 decoded max-length:65000

@apiSuccessExample Success-Response:
    HTTP/1.1 200 OK
    {
        "success": true,
        "code": 0,
        "locale": "en",
        "message": "OK",
        "data": null
    }

@apiError DeviceNotFound Device not found
@apiErrorExample Device not found:
      HTTP/1.1 400 Bad Request
      {
          "success": false,
          "code": 1,
          "locale": "en",
          "message": "Error #1",
          "data": null
      }

@apiError InvalidRequestData Invalid Request Data
@apiErrorExample Invalid Request Data:
    HTTP/1.1 400 Bad Request
    {
        "success": false,
        "code": 15,
        "locale": "en",
        "message": "The given data was invalid.",
        "data": {
            "messages": {
                "deviceId": [
                    "The device id field is required."
                ]
            }
        }
    }
###
