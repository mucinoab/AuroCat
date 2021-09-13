// UUID (Universally Unique IDentifier)
function uuidUnique(): string {
    //From https://stackoverflow.com/a/2117523/12868417
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
      const r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }

export default uuidUnique;