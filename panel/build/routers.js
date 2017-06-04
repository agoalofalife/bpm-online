fs = require('fs')
sup = require('../support/array')

var dir                  = '../src/resource/logs/'
// var dir                  = '../../src/resource/logs/'
var regexDate            = /[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}/g
var regexDurationRequest = /:([0-9]\.[0-9]+)/g
var onlyDate             = /[0-9]{4}-[0-9]{2}-[0-9]{2}/g
var result               = []


function statRequestHandler(req, res) {
        if (fileExist) {
            files = fs.readdirSync(dir)
            if (files.length == 0 ){
                return res.status(500).json( 'while there is no logs files' )
            }
            files.forEach(function(file) {
                var strFromFile         = fs.readFileSync(dir + file, {encoding: 'utf-8'})
                var dateList     = strFromFile.match(regexDate)
                var durationList = getListDurationRequest(strFromFile)

                if (dateList.length != durationList.length) {
                    res.json('does not match the number')
                } else {
                    res.json( JSON.stringify({
                        date      : dateList,
                        durations : durationList

                    }))
                }
            })
        }else{
            res.status(500).json( 'while there is no logs files' )
        }
}

function getListDates(req, res) {
    if (fileExist()) {
        files = fs.readdirSync(dir)
        files.forEach(function(file) {
            var strFromFile = fs.readFileSync(dir + file, {encoding: 'utf-8'})
            var dateList = strFromFile.match(onlyDate)

            res.json( JSON.stringify({
                date :  sup.unique(dateList)
            }))
        })
    } else {
        res.status(500).json( 'while there is no logs files' )
    }
}


function initRouter(app) {

    app.get('/api/statistic-request', function(req, res) {
       return statRequestHandler(req, res)
    })

    app.get('/api/listDates', function(req, res) {
        return getListDates(req, res)
    })
}

// check file exist
function fileExist() {
    return fs.existsSync(dir)
}

// return list duration request
function getListDurationRequest(strFromFile) {
    var result = []
    var duration
    while (duration = regexDurationRequest.exec(strFromFile)) {
          result.push(duration[1])
    }
    return result
}

module.exports = {
    registerRouters : initRouter
}
