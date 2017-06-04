function contains(arr, v) {
    for(var i = 0; i < arr.length; i++) {
        if(arr[i] === v) return true;
    }
    return false;
};

function unique(base) {
    var arr = [];
    for(var i = 0; i < base.length; i++) {
        if(!contains(arr, base[i])) {
            arr.push(base[i]);
        }
    }
    return arr;
}

module.exports = {
    contains : contains,
    unique   : unique
}