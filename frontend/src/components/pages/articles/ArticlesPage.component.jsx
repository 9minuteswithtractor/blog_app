//  get req to server and fetch articles ...
// display articles on screen ...

import axios from "axios";
import { useEffect, useState } from "react";

const ArticlesPage = () => {
  const [user, setUser] = useState("");
  const [articles, setArticles] = useState(null);
  const [error, setError] = useState("");

  // fetch all articles on init state :
  useEffect(() => {
    const fetchAllArticles = async () => {
      const baseApi = "http://localhost:8000/api";
      try {
        const response = await axios.get(`${baseApi}/articles`);
        console.log(response.data);

        // setArticles('')
      } catch (err) {
        setError(err.message); // Update state with the error message
      } finally {
        setLoading(false); // Set loading to false when the request is complete
      }
    };

    fetchAllArticles(); // Call the function
  }, []);

  const fetchAllArticles = async () => {
    const baseApi = "http://localhost:8000/api";

    try {
      const response = await axios.get(`${baseApi}/articles`);
      console.log(response.data);
    } catch (err) {
      setError(err);
    }
  };
  // .map => title, content, author ..
  return <div></div>;
};

export default ArticlesPage;
