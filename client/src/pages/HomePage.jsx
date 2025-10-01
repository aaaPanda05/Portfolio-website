import { Link } from "react-router-dom"; // ✅


function Home() {
  return (
    <div>
      <h1>Welcome to my API test</h1>
      <p>
        <Link to="/docs">Go to API Documentation</Link>
      </p>
    </div>
  );
}

export default Home;