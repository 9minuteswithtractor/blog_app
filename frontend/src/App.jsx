import { BrowserRouter as Router, Route, Routes } from "react-router-dom";

import PageContainer from "./components/layout/PageContainer.component";
import NavBar from "./components/navbar/NavBar.component";
import HomePage from "./components/pages/home/HomePage.component";
import ArticlesPage from "./components/pages/articles/ArticlesPage.component";
import LoginPage from "./components/pages/auth/LoginPage.component";

import "./App.scss";

function App() {
  return (
    <div className="App">
      <Router>
        <div className="main-layout">
          <NavBar />
          <Routes>
            <Route
              path="/"
              element={
                <PageContainer title="Welcome!" content={<HomePage />} />
              }
            />
            <Route
              path="/articles"
              element={
                <PageContainer
                  title="Catch up with latest Posts!"
                  content={<ArticlesPage />}
                />
              }
            />
            <Route
              path="/login"
              element={
                <PageContainer
                  title="Enter Your login details"
                  content={<LoginPage />}
                />
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
