import { useState } from "react";
import axios from "axios";

import "./LoginPage.styles.scss";

const LoginPage = () => {
  const [user, setUser] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");
  const [isLoggedIn, setIsLoggedIn] = useState(false);

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
  const handleOnFormSubmit = async (event) => {
    event.preventDefault(); // so the page doesn't reload ...
    await login();
  };

  const login = () => {
    const formData = {
      user: user,
      password: password,
    };

    const baseApi = "http://app.localhost:80/api";
    try {
      const response = axios.post(`${baseApi}/login`, formData, {
        headers: { "Content-Type": "application/json" },
      });

      const result = response.data;
      setMessage(result);
    } catch (error) {
      console.log("Could not log in", error);
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
      <div>{message}</div>
    </>
  );
};

export default LoginPage;
