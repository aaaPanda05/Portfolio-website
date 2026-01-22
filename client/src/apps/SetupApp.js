import { Routes, Route, NavLink } from "react-router-dom";
import Setup from "../pages/Setup";
import SwaggerDocs from "../pages/SwaggerDocs";
import FinalizeSetup from "../pages/FinalizeSetup";

function SetupApp() {

  return (
    <div className="flex h-screen bg-neutral-900 text-white">
      {/* Sidebar */}
      <aside className="w-56 h-full bg-neutral-900 text-white flex flex-col p-6 border-r border-neutral-700">
        {/* Title */}
        <h2 className="text-xl font-bold text-cyan-400 mb-6">API Builder</h2>

        {/* Navigation */}
        <nav className="flex flex-col gap-2">
          <NavLink
            to="/setup"
            className={({ isActive }) =>
              `px-3 py-2 rounded-md font-medium transition-colors ${
                isActive
                  ? "bg-cyan-400 text-neutral-900"
                  : "text-white hover:bg-neutral-800"
              }`
            }
          >
            Setup
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
            Swagger
          </NavLink>

          <NavLink
            to="/finalize"
            className={({ isActive }) =>
              `px-3 py-2 rounded-md font-medium transition-colors ${
                isActive
                  ? "bg-cyan-400 text-neutral-900"
                  : "text-white hover:bg-neutral-800"
              }`
            }
          >
            Finalize Setup
          </NavLink>
        </nav>

        {/* Bottom version text */}
        <div className="mt-auto text-neutral-400 text-sm">v1.0.0</div>
      </aside>

      {/* Main content */}
      <main className="flex-1 p-6 overflow-y-auto">
        <Routes>
          <Route path="/setup" element={<Setup />} />
          <Route path="/swagger" element={<SwaggerDocs />} />
          <Route path="/finalize" element={<FinalizeSetup />} />
        </Routes>
      </main>
    </div>
  );
}

export default SetupApp;
