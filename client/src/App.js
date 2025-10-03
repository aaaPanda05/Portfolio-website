import React from "react";
import { Routes, Route, NavLink } from "react-router-dom";
import Dashboard from "./pages/Dashboard";
import SwaggerDocs from "./pages/SwaggerDocs";

function App() {
  return (
    <div id="main-page" className="flex h-screen bg-neutral-900 text-white">
      {/* Sidebar */}
      <aside className="w-56 h-full bg-neutral-900 text-white flex flex-col p-6 border-r border-neutral-700">
        {/* Title */}
        <h2 className="text-xl font-bold text-cyan-400 mb-6">
          API Builder
        </h2>

        {/* Navigation */}
        <nav className="flex flex-col gap-2">
          <NavLink
            to="/"
            end
            className={({ isActive }) =>
              `px-3 py-2 rounded-md font-medium transition-colors ${
                isActive
                  ? "bg-cyan-400 text-neutral-900"
                  : "text-white hover:bg-neutral-800"
              }`
            }
          >
            Dashboard
          </NavLink>

          <NavLink
            to="/swagger"
            className={({ isActive }) =>
              `px-3 py-2 rounded-md font-medium transition-colors ${
                isActive
                  ? "bg-cyan-400 text-neutral-900"
                  : "text-white hover:bg-neutral-800"
              }`
            }
          >
            API Documentation
          </NavLink>
        </nav>

        {/* Bottom section */}
        <div className="mt-auto text-neutral-400 text-sm">
          v1.0.0
        </div>
      </aside>

      {/* Main content */}
      <main className="flex-1 p-6 overflow-y-auto">
        <Routes>
          <Route path="/" element={<Dashboard />} />
          <Route path="/swagger" element={<SwaggerDocs />} />
        </Routes>
      </main>
    </div>
  );
}

export default App;
