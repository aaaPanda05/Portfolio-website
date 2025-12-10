import React, { useState } from "react";

export default function ModelControllerForm({onSave, onNext}) {
    const [className, setClassName] = useState("");
    const [feedback, setFeedback] = useState("");

    const [properties, setProperties] = useState([
        { name: "", access: "public" },
    ]);

    const [methods, setMethods] = useState([
        { name: "", access: "public" },
    ]);

    const updateItem = (list, setList, index, field, value) => {
        const updated = [...list];
        updated[index][field] = value;
        setList(updated);
    };

    const removeItem = (list, setList, index) => {
        const updated = list.filter((_, i) => i !== index);
        setList(updated);
    };

    const addProperty = () => {
        setProperties([...properties, { name: "", access: "public" }]);
    };

    const addMethod = () => {
        setMethods([...methods, { name: "", access: "public" }]);
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        const payload = {
            className,
            properties,
            methods
        };

        onSave(payload);
        onNext();

        setFeedback("Class + properties/methods generated!");
    };

    return (
        <div className="bg-neutral-900 text-white p-6 rounded-xl shadow-md w-full max-w-lg mx-auto">
            <h2 className="text-xl font-semibold mb-6">Setup model/controllers</h2>

            <form onSubmit={handleSubmit} className="space-y-6">

                {/* Class name */}
                <div>
                    <label className="block text-sm font-medium mb-1">Class name</label>
                    <input
                        type="text"
                        value={className}
                        onChange={(e) => setClassName(e.target.value)}
                        placeholder="Example: User, Product"
                        className="w-full rounded-md bg-neutral-800 border border-neutral-700 p-2"
                        required
                    />
                </div>

                {/* Properties */}
                <div>
                    <label className="block text-sm font-medium mb-2">Properties</label>
                    <div className="space-y-3">
                        {properties.map((prop, index) => (
                            <div key={index} className="flex items-center gap-2 bg-neutral-800 p-2 rounded-md">

                                <input
                                    type="text"
                                    placeholder="Property name"
                                    value={prop.name}
                                    onChange={(e) =>
                                        updateItem(properties, setProperties, index, "name", e.target.value)
                                    }
                                    className="flex-1 bg-neutral-900 border border-neutral-700 p-2 rounded-md"
                                />

                                <select
                                    value={prop.access}
                                    onChange={(e) =>
                                        updateItem(properties, setProperties, index, "access", e.target.value)
                                    }
                                    className="bg-neutral-900 border border-neutral-700 p-2 rounded-md"
                                >
                                    <option value="public">public</option>
                                    <option value="private">private</option>
                                    <option value="protected">protected</option>
                                </select>

                                <button
                                    type="button"
                                    onClick={() => removeItem(properties, setProperties, index)}
                                    className="text-red-400 hover:text-red-500"
                                >
                                    ❌
                                </button>
                            </div>
                        ))}
                    </div>

                    <button
                        type="button"
                        onClick={addProperty}
                        className="mt-3 w-full bg-neutral-800 hover:bg-neutral-700 text-sm py-2 rounded-md"
                    >
                        ➕ Add Property
                    </button>
                </div>

                {/* Methods */}
                <div>
                    <label className="block text-sm font-medium mb-2">Methods</label>
                    <div className="space-y-3">
                        {methods.map((method, index) => (
                            <div key={index} className="flex items-center gap-2 bg-neutral-800 p-2 rounded-md">

                                <input
                                    type="text"
                                    placeholder="Method name"
                                    value={method.name}
                                    onChange={(e) =>
                                        updateItem(methods, setMethods, index, "name", e.target.value)
                                    }
                                    className="flex-1 bg-neutral-900 border border-neutral-700 p-2 rounded-md"
                                />

                                <select
                                    value={method.access}
                                    onChange={(e) =>
                                        updateItem(methods, setMethods, index, "access", e.target.value)
                                    }
                                    className="bg-neutral-900 border border-neutral-700 p-2 rounded-md"
                                >
                                    <option value="public">public</option>
                                    <option value="private">private</option>
                                    <option value="protected">protected</option>
                                </select>

                                <button
                                    type="button"
                                    onClick={() => removeItem(methods, setMethods, index)}
                                    className="text-red-400 hover:text-red-500"
                                >
                                    ❌
                                </button>
                            </div>
                        ))}
                    </div>

                    <button
                        type="button"
                        onClick={addMethod}
                        className="mt-3 w-full bg-neutral-800 hover:bg-neutral-700 text-sm py-2 rounded-md"
                    >
                        ➕ Add Method
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
                <div className="fixed bottom-4 right-4 bg-neutral-800 text-white px-4 py-2 rounded-md shadow-lg">
                    {feedback}
                    <button onClick={() => setFeedback("")} className="ml-2 text-red-400 hover:text-red-500">
                        ✖
                    </button>
                </div>
            )}
        </div>
    );
}
