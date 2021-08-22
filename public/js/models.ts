// This file is just for the various interfaces needed.

interface MsgPackage {
  id: string,
  msg: string | undefined,
  time: number,
  side: string,
  instanceId: string | undefined,
  callback: Callback | undefined,
}

interface Callback {
  data: string,
  practiceGame: boolean,
}
