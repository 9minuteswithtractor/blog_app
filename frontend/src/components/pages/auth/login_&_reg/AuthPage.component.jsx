import { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";

import "./AuthPage.styles.scss";

// here we need to pass back user, loggedIn -> parentComponent
const LoginPage = ({ setUserName, setLoginState }) => {
  const [user, setUser] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");
  const [loggedIn, setLoggedIn] = useState(false);
  const [error, setError] = useState("");

  const navigate = useNavigate();

  const clearAndRedirect = (status) => {
    if (status) {
      sessionStorage.setItem("userName", user);
      sessionStorage.setItem("isLoggedIn", status);

      // setUserName(user);

      setTimeout(() => {
        setUser("");
        setPassword("");
        navigate("/articles");
      }, 1000);
    }
  };

  const handleOnUserChange = (event) => {
    const { value } = event.target;
    setUser(value);
  };

  const handleOnPasswordChange = (event) => {
    const { value } = event.target;
    setPassword(value);
  };

  const handleOnFormSubmit = (event) => {
    event.preventDefault();
    login();
  };

  const handleRegister = (event) => {
    event.preventDefault();
    register();
  };

  const register = async () => {
    const formData = {
      user: user,
      password: password,
    };

    const baseApi = "http://localhost:8000/api";
    try {
      const response = await axios.post(`${baseApi}/register`, formData, {
        headers: { "Content-Type": "application/json" },
      });

      console.log("Sending reg form data to server..");

      const result = response.data;
      const { message, isLoggedIn, error } = result;

      console.log(result);
      setMessage(message);
      setLoggedIn(isLoggedIn);
      setError(error);
      clearAndRedirect(isLoggedIn);
    } catch (err) {
      setError(err);
    }
  };

  const login = async () => {
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
      const { message, isLoggedIn, error } = result;

      console.log(result);

      setMessage(message);
      setError(error);
      clearAndRedirect(isLoggedIn);
    } catch (err) {
      setError(err);
    }
  };

  return (
    <>
      <div className="login-output">
        {loggedIn ? (
          <div className="login-message-container success">
            <h3>{message}</h3>
          </div>
        ) : (
          <div className="login-message-container fail">
            <h3>{error}</h3>
          </div>
        )}
      </div>
      <form className="login-form" onSubmit={handleOnFormSubmit}>
        <div className="username-container">
          <label htmlFor="user">username: </label>
          <input
            type="text"
            id="user"
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
            value={password}
            onChange={handleOnPasswordChange}
            required
          />
        </div>
        <button type="submit" name="login" value="login">
          Login
        </button>
        <button type="button" value="register" onClick={handleRegister}>
          Register
        </button>
      </form>
    </>
  );
};

export default LoginPage;
