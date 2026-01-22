import { useEffect, useState } from "react";
import systemApi from "./services/systemApi";

import SetupApp from "./apps/SetupApp";
import AdminApp from "./apps/AdminApp";
import WebsiteApp from "./apps/WebsiteApp";


function App() {
  const [systemState, setSystemState] = useState(null);

  useEffect(() => {
    systemApi.getStatus().then(setSystemState);
  }, []);

  if (!systemState) return <div>Loading…</div>;

  if (!systemState.locked) {
    return <SetupApp/>;
  }

  return (
    <>
      {/* <AdminApp /> */}
      <WebsiteApp />
    </>
  );
}


export default App;
