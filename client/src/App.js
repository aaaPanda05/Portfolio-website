import React from "react";
import { Routes, Route, Link } from "react-router-dom";
import Dashboard from "./pages/Dashboard";
import SwaggerDocs from "./pages/SwaggerDocs";
import './App.css';

function App() {
  return (
    <div id="main-page">
      {/* Sidebar */}
      <aside className="sidebar">
        <h2>API Builder</h2>
        <nav>
          <Link to="/">Dashboard</Link>
          <Link to="/swagger"> API Documentation</Link>
        </nav>
      </aside>

      {/* Main content */}
      <main className="main-content">
        <Routes>
          <Route path="/" element={<Dashboard />} />
          <Route path="/swagger" element={<SwaggerDocs />} />
        </Routes>
      </main>
    </div>
  );
}

export default App;
