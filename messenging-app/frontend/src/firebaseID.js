
import { get, onValue, ref as dbRef } from "firebase/database";
import { app, analytics, db } from "./firebase.js";

const getUsernameByUid = async (uid) => {
  const snapshot = await get(onValue(dbRef(db, `users/${uid}`)));
  return snapshot.val()?.username;
};

export { getUsernameByUid };
