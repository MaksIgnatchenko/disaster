###
@api {put} /api/settings Update user's settings
@apiName Update user's settings
@apiGroup Settings
@apiVersion 0.1.0

@apiHeader {String} Accept application/json
@apiHeader {String} Content-Type application/x-www-form-urlencoded
@apiHeader {String} deviceId deviceId

@apiParam (Body fields) {String} tempUnit Temperature unit ENUM : [c, f]
@apiParam (Body fields) {String} windSpeedUnit Wind speed unit ENUM : [kph, mph, m/s]
@apiParam (Body fields) {Integer} minTemp Require with maxTemp min:-100 max:100 Less than maxTemp
@apiParam (Body fields) {Integer} maxTemp Require with minTemp min:-100 max:100 Greater than minTemp
@apiParam (Body fields) {String} timezone Timezone (format - Europe/Kiev)
@apiParam (Body fields) {Array} locations Multidimensional array locations[n][country] locations[n][place]
@apiParam (Body fields) {Array} disasterCategories Array of strings (abbreviations of events)

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
                "minTemp": [
                    "The min temperature must be greater than max temperature."
                ]
            }
        }
    }
###

