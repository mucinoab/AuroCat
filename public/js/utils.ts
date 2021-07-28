// Crea un nuevo elemento HTML
function newElement(tagType: string, className: string, val: any): HTMLElement {
  const ele = document.createElement(tagType);
  ele.className = className;
  ele.innerText = val;

  return ele;
}

// Manda post request a url con datos en json
async function postData(url: string, data: { [key: string]: any }) {
  // De MDN, https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch#supplying_request_options
  await fetch(url, {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data),
  });
}