"use strict";
function newElement(tagType, className, val) {
    const ele = document.createElement(tagType);
    ele.className = className;
    ele.innerText = val;
    return ele;
}
async function postData(url, data) {
    await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    });
}
