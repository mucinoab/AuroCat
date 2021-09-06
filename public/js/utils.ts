// Creates new HTML element.
function newElement(tagType: string, className: string = "", val: any = ""): HTMLElement {
  const ele = document.createElement(tagType);
  ele.className = className;
  ele.innerText = val;

  return ele;
}

// Gets an element by id or creates a new one with the provided tag type.
function getOrNew(id: string, tagType: string, className: string = ""): HTMLElement {
  const ele = document.getElementById(id);

  if (ele !== null) return ele;

  return newElement(tagType, className);
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

function unixTime(): number{
  // Real unix time is in seconds, not milliseconds.
  return Math.floor(new Date().getTime() / 1000);
}

function timeFromUnix(timeStamp: string | number): string {
  return new Date(timeStamp).toLocaleTimeString([], {
    hour: "2-digit",
    minute:"2-digit",
    hour12: false,
  });
}

// Send a notification and ask for permission if not granted already
function notify(title: string, msg: string) {
  if (document.visibilityState === 'visible' || title === undefined) return;

  if (Notification.permission === "granted") {
    spawnNotification(title, msg);
  } else if (Notification.permission !== "denied") {
    // Ask for permission.
    Notification.requestPermission().then(permission => {
      if (permission === "granted") spawnNotification(title, msg);
    });
  }
}

function spawnNotification(title: string, content: string, ) {
  // From MDN, https://developer.mozilla.org/en-US/docs/Web/API/Notification/close#examples

  const options = {
    body: content,
    icon: "/images/logo.png",
  };

  const n = new Notification(title, options);

  document.addEventListener('visibilitychange', _ => {
    if (document.visibilityState === 'visible') {
      n.close();
    }
  }, {once : true});
}

