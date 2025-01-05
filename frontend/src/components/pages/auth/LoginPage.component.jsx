import { useState } from "react";
import axios from "axios";
import { useNavigate } from 'react-router-dom';

import "./LoginPage.styles.scss";

const LoginPage = () => {
  const [user, setUser] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");
  const [loginAttempt, setLoginAttempt] = useState(false);
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleOnUserChange = (event) => {
    const { value } = event.target;
    setUser(value);
  };

  const handleOnPasswordChange = (event) => {
    const { value } = event.target;
    setPassword(value);
  };

  /**
   *  post req to backend
   *  Q : ? should this function be async or thats only necessary for get requests ?
   */
  const handleOnFormSubmit = (event) => {
    event.preventDefault(); // so the page doesn't reload ...
    login();
  };

  const login = async () => {
    setLoginAttempt(true);

    const formData = {
      user: user,
      password: password,
    };

    const baseApi = "http://localhost:8000/api";
    try {
      const response = await axios.post(`${baseApi}/login`, formData, {
        headers: { "Content-Type": "application/json" },
      });

      const result = response.data;
      debugger;
      if (result.success) {
        console.log(result.message);
        navigate('/articles');
      } else {
        console.log(result.message);
        setMessage(result.message);
      }
    } catch (err) {
      console.log(err);
    }
  };

  return (
    <>
      <form className="login-form" onSubmit={handleOnFormSubmit}>
        <div className="username-container">
          <label htmlFor="user">username: </label>
          <input
            type="text"
            id="user"
            name="user"
            value={user}
            onChange={handleOnUserChange}
            required
          />
        </div>
        <div className="password-container">
          <label htmlFor="password">password: </label>
          <input
            type="password"
            id="password"
            name="password"
            value={password}
            onChange={handleOnPasswordChange}
            required
          />
        </div>
        <button type="submit">Login</button>
      </form>
      {/* debug purpose */}
      {loginAttempt && (
        <div style={{ marginTop: "15px" }}>
          username: {user} <br /> error message: {message}
        </div>
      )}
    </>
  );
};

export default LoginPage;
