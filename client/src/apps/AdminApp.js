import { Routes, Route } from "react-router-dom";

function AdminApp() {
  return (
    <div className="flex h-screen bg-neutral-900 text-white">
      <aside className="w-56 p-6 border-r border-neutral-700">
        <h2 className="text-xl font-bold text-cyan-400 mb-6">Admin Panel</h2>
      </aside>

      <main className="flex-1 p-6">
        <Routes>
          <Route path="/admin" element={<div>Dashboard</div>} />
        </Routes>
      </main>
    </div>
  );
}

export default AdminApp;
