import React, { useEffect, useState } from 'react';
import '../css/routelist.css'; // or './Routes.css' if you created a separate file

function RoutesList() {
  const [routes, setRoutes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchRoutes = async () => {
      try {
        const response = await fetch("http://localhost:5000/generator/routes");
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();

        // Transform data into an array of { method, path }
        const routesArray = [];
        for (const method in data) {
          for (const path in data[method]) {
            routesArray.push({ method, path });
          }
        }

        setRoutes(routesArray);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchRoutes();
  }, []);

  if (loading) return <p>Loading routes...</p>;
  if (error) return <p>Error: {error}</p>;

  return (
    <div className="routes-container">
      <h2>Routes</h2>
      <table className="routes-table">
        <thead>
          <tr>
            <th>Method</th>
            <th>Path</th>
          </tr>
        </thead>
        <tbody>
          {routes.map((route, index) => (
            <tr key={index}>
              <td>{route.method}</td>
              <td>{route.path}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default RoutesList;
