import React from 'react';

const sendResources = async (data) => {
    try {
        console.log(data);
        const response = await fetch(`${process.env.REACT_APP_API_URL}/generator/generate`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
        });
        return await response.json();
    } catch (err) {
        throw err;
    }
};

export default sendResources;


