const BASE_URL = process.env.REACT_APP_API_URL;

async function getStatus() {
    try {
        const response = await fetch(`${BASE_URL}/__system/status`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();
        return data; 
    } catch (error) {
        console.error("Error fetching system status:", error);
        throw error; 
    }
}

async function finalizeSetup(password) {
    try {
        const response = await fetch(`${BASE_URL}/__system/finalize`, {
            method: "POST",
            body: password,
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();
        console.log(data)
    } catch (error) {
        console.error("Error fetching system status:", error);
        throw error; 
    }
}

export default { getStatus, finalizeSetup };
