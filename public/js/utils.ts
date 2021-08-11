// Creates new HTML element
function newElement(tagType: string, className: string, val: any): HTMLElement {
  const ele = document.createElement(tagType);
  ele.className = className;
  ele.innerText = val;

  return ele;
}

// Manda post request a url con datos en json
async function postData(url: string, data: { [key: string]: any }): Promise<void> {
  // From MDN, https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch#supplying_request_options
  await fetch(url, {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data),
  });
}

// UUID (Universally Unique IDentifier)
function uuid(): string {
  //From https://stackoverflow.com/a/2117523/12868417
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
    const r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}
