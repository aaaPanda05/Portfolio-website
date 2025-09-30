// src/pages/SwaggerDocs.jsx
import React from "react";
import SwaggerUI from "swagger-ui-react";
import "swagger-ui-react/swagger-ui.css";

const SwaggerDocs = () => (
  <div style={{ margin: "2rem" }}>
    <h1>API Documentation</h1>
    <SwaggerUI url="http://localhost:5000/swagger/json" />
  </div>
);

export default SwaggerDocs;
