import React, { useState } from "react";
import sendResources from "../api/sendResources.js";

export default function ResourceForm() {
  const [modelName, setModelName] = useState("");
  const [feedback, setFeedback] = useState(""); 
  const [fields, setFields] = useState([
    { name: "", type: "string", required: false, unique: false },
  ]);

  const mapToSQLType = (type) => {
    switch (type) {
      case "string":
        return "string";
      case "number":
        return "int";
      case "boolean":
        return "bool";
      case "date":
        return "date";
      default:
        return "string";
    }
  };

  const handleFieldChange = (index, key, value) => {
    const newFields = [...fields];
    newFields[index][key] = value;
    setFields(newFields);
  };

  const addField = () => {
    setFields([
      ...fields,
      { name: "", type: "string", required: false, unique: false },
    ]);
  };

  const removeField = (index) => {
    setFields(fields.filter((_, i) => i !== index));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!modelName) return alert("Please enter a model name");

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
          ...(idx === 0 && field.name.toLowerCase() === "id"
            ? { primaryKey: true, autoIncrement: true }
            : {}),
        })),
      },
    };

    
    const { feedback } = await sendResources(payload);
    setFeedback(feedback);
  };

  return (
    <div className="bg-neutral-900 text-white p-6 rounded-xl shadow-md w-full max-w-lg mx-auto">
      <h2 className="text-xl font-semibold mb-6">Create Model</h2>
      <form onSubmit={handleSubmit} className="space-y-6">
        {/* Model Name */}
        <div>
          <label className="block text-sm font-medium mb-1">Model Name</label>
          <input
            type="text"
            value={modelName}
            onChange={(e) => setModelName(e.target.value)}
            required
            className="w-full rounded-md bg-neutral-800 border border-neutral-700 p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="e.g. User"
          />
        </div>

        {/* Fields */}
        <div>
          <label className="block text-sm font-medium mb-2">Columns</label>
          <div className="space-y-3">
            {fields.map((field, index) => (
              <div
                key={index}
                className="flex items-center gap-2 bg-neutral-800 p-2 rounded-md"
              >
                <input
                  type="text"
                  placeholder="Column Name"
                  value={field.name}
                  onChange={(e) =>
                    handleFieldChange(index, "name", e.target.value)
                  }
                  required
                  className="flex-1 rounded-md bg-neutral-900 border border-neutral-700 p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                />

                <select
                  value={field.type}
                  onChange={(e) =>
                    handleFieldChange(index, "type", e.target.value)
                  }
                  className="rounded-md bg-neutral-900 border border-neutral-700 p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                >
                  <option value="string">String</option>
                  <option value="number">Number</option>
                  <option value="boolean">Boolean</option>
                  <option value="date">Date</option>
                </select>

                <label className="flex items-center gap-1 text-xs">
                  <input
                    type="checkbox"
                    checked={field.required}
                    onChange={(e) =>
                      handleFieldChange(index, "required", e.target.checked)
                    }
                  />
                  Req
                </label>

                <label className="flex items-center gap-1 text-xs">
                  <input
                    type="checkbox"
                    checked={field.unique}
                    onChange={(e) =>
                      handleFieldChange(index, "unique", e.target.checked)
                    }
                  />
                  Unique
                </label>

                <button
                  type="button"
                  onClick={() => removeField(index)}
                  className="text-red-400 hover:text-red-500"
                >
                  ❌
                </button>
              </div>
            ))}
          </div>

          <button
            type="button"
            onClick={addField}
            className="mt-3 w-full bg-neutral-800 hover:bg-neutral-700 text-sm text-white py-2 rounded-md"
          >
            ➕ Add Column
          </button>
        </div>

        <button
          type="submit"
          className="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md font-medium"
        >
          Generate
        </button>
      </form>

      {feedback && (
        <div className="fixed bottom-4 right-4 bg-neutral-800 text-white px-4 py-2 rounded-md shadow-lg animate-fade-in">
            {feedback}
            <button
            onClick={() => setFeedback("")}
            className="ml-2 text-red-400 hover:text-red-500"
            >
            </button>
        </div>
        )}
    </div>
  );
}
