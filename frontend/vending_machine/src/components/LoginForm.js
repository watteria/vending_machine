
import React from 'react';

// Gestiona Login
function LoginForm({ password, setPassword, handlePasswordSubmit,loading,setMostrarFormulario  }) {
    return (
        <div className="container mt-4">
            <div className="card">

                <div className="card-body">
                    <form onSubmit={handlePasswordSubmit}>
                        <div className="form-group mb-3">
                            <label htmlFor="password">Enter the password (for this demo it is "password"):</label>
                            <input
                                type="password"
                                id="password"
                                className="form-control"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required
                                disabled={loading}
                            />
                        </div>
                        <div className="text-center">
                            <button type="submit" disabled={loading} className="btn btn-primary  me-2">
                                {loading ? "Loading..." : "Validate"}
                            </button>
                            <button
                                type="button"
                                onClick={() => setMostrarFormulario(false)}
                                className="btn btn-secondary close"
                            >
                                Close
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    );
}

export default LoginForm;