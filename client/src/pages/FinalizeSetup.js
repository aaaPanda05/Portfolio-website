import { useEffect, useState } from "react";
import systemApi from "../services/systemApi";

function FinalizeSetup() {
    const [systemState, setSystemState] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [password, setPassword] = useState("");
    const [ready, setReady] = useState(false);
    const [submitSuccess, setSubmitSuccess] = useState(false);

    // Fetch system state on mount
    useEffect(() => {
        const fetchStatus = async () => {
        try {
            const status = await systemApi.getStatus();
            console.log("System status:", status);
            setSystemState(status);
        } catch (err) {
            console.error("Failed to fetch system status:", err);
            setError("Unable to fetch system status");
        } finally {
            setLoading(false);
        }
        };

        fetchStatus();
    }, []);



    // Handle finalize submission
    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!ready || !password) return;

        try {
            setLoading(true);
            await systemApi.finalizeSetup(password); // call backend to lock system
            setSubmitSuccess(true);
            setSystemState({ ...systemState, locked: true, status: "finalized" });
        } catch (err) {
            console.error("Finalize failed:", err);
            setError("Failed to finalize setup");
        } finally {
            
        }
    };

    // Render loading / error states first
    if (loading) return <div>Loading system status...</div>;
    if (error) return <div>Error: {error}</div>;

    // If system is not initialized, redirect or show warning
    if (systemState?.status === "not_initialized")
        return <div>Please complete setup first</div>;

    // If already finalized, redirect to admin login / panel
    if (systemState?.status === "finalized")
        return <div>System already finalized. Please log in to admin panel.</div>;

    // Finalize form
    return (
        <div>
        <h1>Finalize Setup</h1>
        <p>Confirm you are ready to enable the admin panel:</p>
        <form onSubmit={handleSubmit}>
            <div>
            <label>
                Admin password:
                <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                />
            </label>
            </div>
            <div>
            <label>
                <input
                type="checkbox"
                checked={ready}
                onChange={(e) => setReady(e.target.checked)}
                />
                I confirm I am ready to finalize the system
            </label>
            </div>
            <button type="submit" disabled={!ready || !password}>
            Finalize Setup
            </button>
        </form>

        {submitSuccess && <p>System finalized! You can now log in to the admin panel.</p>}
        </div>
    );
}

export default FinalizeSetup;
