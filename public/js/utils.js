"use strict";
function newElement(tagType, className = "", val = "") {
    const ele = document.createElement(tagType);
    ele.className = className;
    ele.innerText = val;
    return ele;
}
function getOrNew(id, tagType, className = "") {
    const ele = document.getElementById(id);
    if (ele !== null)
        return ele;
    return newElement(tagType, className);
}
async function postData(url, data) {
    await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    });
}
function uuid() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
        const r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}
function unixTime() {
    return Math.floor(new Date().getTime() / 1000);
}
function timeFromUnix(timeStamp) {
    return new Date(timeStamp).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    });
}
