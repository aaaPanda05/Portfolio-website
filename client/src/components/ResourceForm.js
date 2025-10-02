import React, { useState } from "react";
import sendResources from "../api/sendResources.js";


export default function ResourceForm() {
    const [modelName, setModelName] = useState("");
    const [fields, setFields] = useState([
        { name: "", type: "string", required: false, unique: false },
    ]);

    // Map frontend types to SQL types
    const mapToSQLType = (type) => {
        switch (type) {
            case "string": return "string";
            case "number": return "int";
            case "boolean": return "bool";
            case "date": return "date"; 
            default: return "string";
        }
    };


    const handleFieldChange = (index, key, value) => {
        const newFields = [...fields];
        newFields[index][key] = value;
        setFields(newFields);
    };

    const addField = () => {
        setFields([...fields, { name: "", type: "string", required: false, unique: false }]);
    };

    const removeField = (index) => {
        setFields(fields.filter((_, i) => i !== index));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        if (!modelName) return alert("Please enter a model name");

        // Construct payload to match backend
        const tableName = modelName.toLowerCase() + "s"; 
        const payload = {
        models: [{ name: modelName, tableName }],
        controllers: [{ name: modelName }],
        table: {
            name: tableName,
            columns: fields.map((field, idx) => ({
            name: field.name,
            type: mapToSQLType(field.type),
            required: field.required || false,
            unique: field.unique || false,
            
            ...(idx === 0 && field.name.toLowerCase() === "id" ? { primaryKey: true, autoIncrement: true } : {})
            })),
        },
        };

        sendResources(payload);
    };

    return (
        <form onSubmit={handleSubmit} style={{ maxWidth: "600px", margin: "auto" }}>
        <h2>Model Generator</h2>

        {/* Model Name */}
        <div style={{ marginBottom: "16px" }}>
            <label>Model Name:</label>
            <input
            type="text"
            value={modelName}
            onChange={(e) => setModelName(e.target.value)}
            required
            style={{ marginLeft: "10px", padding: "6px", width: "60%" }}
            />
        </div>

        {/* Fields */}
        <h3>Fields</h3>
        {fields.map((field, index) => (
            <div
            key={index}
            style={{
                display: "flex",
                gap: "10px",
                marginBottom: "8px",
                alignItems: "center",
            }}
            >
            <input
                type="text"
                placeholder="Column Name"
                value={field.name}
                onChange={(e) => handleFieldChange(index, "name", e.target.value)}
                required
            />

            <select
                value={field.type}
                onChange={(e) => handleFieldChange(index, "type", e.target.value)}
            >
                <option value="string">String</option>
                <option value="number">Number</option>
                <option value="boolean">Boolean</option>
                <option value="date">Date</option>
            </select>

            <label>
                <input
                type="checkbox"
                checked={field.required}
                onChange={(e) => handleFieldChange(index, "required", e.target.checked)}
                />
                Required
            </label>

            <label>
                <input
                type="checkbox"
                checked={field.unique}
                onChange={(e) => handleFieldChange(index, "unique", e.target.checked)}
                />
                Unique
            </label>

            <button type="button" onClick={() => removeField(index)}>
                ❌
            </button>
            </div>
        ))}

        <button type="button" onClick={addField} style={{ marginBottom: "16px" }}>
            ➕ Add Field
        </button>

        <br />

        <button type="submit">Generate</button>
        </form>
    );
}
