// src/App.js
import React from "react";
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import SwaggerDocs from "./pages/SwaggerDocs";

function Home() {
  return (
    <div>
      <h1>Welcome to my API Builder</h1>
      <p>
        <Link to="/docs">Go to API Documentation</Link>
      </p>
    </div>
  );
}

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/docs" element={<SwaggerDocs />} />
      </Routes>
    </Router>
  );
}

export default App;
