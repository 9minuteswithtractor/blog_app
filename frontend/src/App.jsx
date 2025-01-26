import { BrowserRouter as Router, Route, Routes } from "react-router-dom";

import PageContainer from "./components/layout/PageContainer.component";
import NavBar from "./components/navbar/NavBar.component";
import HomePage from "./components/pages/home/HomePage.component";
import ArticlesPage from "./components/pages/articles/ArticlesPage.component";
import LoginPage from "./components/pages/auth/login_&_reg/AuthPage.component";

import "./App.scss";
import { useState, useEffect } from "react";

function App() {
  const [userName, setUserName] = useState(() => {
    return sessionStorage.getItem("userName");
  });
  const [loginState, setLoginState] = useState(() => {
    return sessionStorage.getItem("isisLoggedIn");
  });

  console.log("App current user : ", userName);
  // useEffect to watch the changes to userName and update sessionStorage accordingly

  // Synchronize userName with sessionStorage when it changes
  useEffect(() => {
    if (userName) {
      sessionStorage.setItem("userName", userName);
    } else {
      sessionStorage.removeItem("userName");
    }
  }, [userName]);

  return (
    <div className="App">
      <Router>
        <div className="main-layout">
          <NavBar userName={userName} setUserName={setUserName} />
          <Routes>
            <Route
              path="/"
              element={
                <PageContainer greeting="Welcome" content={<HomePage />} />
              }
            />

            <Route
              path="/articles"
              element={
                <PageContainer
                  greeting="Catch up with latest Posts"
                  content={<ArticlesPage />}
                />
              }
            />
            <Route
              path="/login"
              element={
                <>
                  <PageContainer
                    greeting="Enter Your login details"
                    content={
                      <LoginPage
                        setUserName={setUserName}
                        setLoginState={setLoginState}
                      />
                    }
                  />
                </>
              }
            />
          </Routes>
          <footer>
            <p>Footer here</p>
          </footer>
        </div>
      </Router>
    </div>
  );
}

export default App;
