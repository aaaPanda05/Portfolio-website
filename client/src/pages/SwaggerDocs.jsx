// src/pages/SwaggerDocs.jsx
import React from "react";
import SwaggerUI from "swagger-ui-react";
import "swagger-ui-react/swagger-ui.css";
import "../css/swaggerdocs.css";

const SwaggerDocs = () => (
  <div style={{ margin: "2rem", background: "white", borderRadius: "8px", padding: "1rem" }}>
    <SwaggerUI url="http://localhost:5000/swagger/json" />
  </div>
);

export default SwaggerDocs;