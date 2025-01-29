import axios from "axios";
import { useEffect, useState } from "react";

import "./ArticlesPage.styles.scss";

const ArticlesPage = () => {
  const [articles, setArticles] = useState([]);
  const [error, setError] = useState("");
  const [post, setPost] = useState(""); // for individual text input when logged in ..

  // session data
  const user = sessionStorage.getItem("userName");
  const loggedInStatus = sessionStorage.getItem("isLoggedIn") === tr;

  // TODO check sessionStorage isLoggedIn ?
  /**
   * if logged in render text input area with submit btn
   *
   * object shape ?
   *  axios post req -> add to articles ...
   *
   * after fetch again all articles
   *
   * handle comments
   */
  const handleOnFormSubmit = () => {};
  const handleOnPostChange = (event) => {};
  // fetch all articles on init state :
  useEffect(() => {
    fetchAllArticles(); // Call the function
  }, []);

  const fetchAllArticles = async () => {
    const baseApi = "http://localhost:8000/api";
    try {
      const response = await axios.get(`${baseApi}/articles`);
      const allReqData = response.data;
      setArticles(allReqData);
      //
    } catch (err) {
      setError(err.message); // Update state with the error message
    } finally {
      // console.log("fetching all articles from server ...");
    }
  };

  // // obj shape : id, title, content, author
  // if (loggedInStatus) {
  //   return (
  //     <>
  //       <form className="post-form" onSubmit={handleOnPostSubmit}>
  //         <div className="post-container">
  //           <input type="text" value={post} onChange={handleOnPostChange} />
  //         </div>
  //         {/* <button type="submit" name="post" value="post">
  //           Post
  //         </button> */}
  //       </form>
  //     </>
  //   );
  // }

  if (articles.length > 0 && !error) {
    return (
      <>
        <ul className="articles-container">
          {articles.map((post) => {
            const { id, title, content, author } = post;
            return (
              <>
                <li className="article-card-container" key={id}>
                  <h2>{title}</h2>
                  <p>{content}</p>
                  <h3>
                    <em>{author}</em>
                  </h3>
                </li>
                <li className="article-card-container" key={id}>
                  <h2>{title}</h2>
                  <p>{content}</p>
                  <h3>
                    <em>{author}</em>
                  </h3>
                </li>
              </>
            );
          })}
        </ul>
      </>
    );
  } else {
    return <h2>No articles found or something went wrong ...{error}</h2>;
  }
};

export default ArticlesPage;
