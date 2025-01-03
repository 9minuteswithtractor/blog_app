import { BrowserRouter as Router, Route, Routes } from "react-router-dom";

import NavBar from "./components/navbar/NavBar.component";
import PageContainer from "./components/page-container/PageContainer.component";
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
                <PageContainer
                  title="Welcome!"
                  // TODO : Create content as PageComponent to display dynamic content ...
                  content="Some info about this page ..."
                />
              }
            />
            <Route
              path="/posts"
              element={
                <PageContainer
                  title="Catch up with latest Posts!"
                  content="Here is List of latest articles"
                />
              }
            />
            <Route
              path="/login"
              element={
                <PageContainer
                  title="Enter Your login details"
                  content="Here is Login Form"
                />
              }
            />
          </Routes>
        </div>
      </Router>
    </div>
  );
}

export default App;
