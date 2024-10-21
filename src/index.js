import apiFetch from "@wordpress/api-fetch";
const { useState, useEffect } = wp.element;

wp.blocks.registerBlockType("custom-plugin/woocommerce-random-products", {
  title: "Random Woocommerce products",
  icon: "smiley",
  category: "common",

  edit: function () {
    const [products, setProducts] = useState([]);

    useEffect(() => {
      apiFetch({ path: "/wc/v3/products" })
        .then((data) => setProducts(data))
        .catch((error) => console.error("Error fetching products:", error));
    }, []);
    return (
      <div className="product_container" style={{ display: "flex" }}>
        {products?.map((product) => (
          <a href={product.permalink} key={product.id}>
            <div>
              <img src={product.images[0]?.src} alt="product image" />
              <h3>{product.name}</h3>
            </div>
          </a>
        ))}
      </div>
    );
  },

  save: function () {
    return wp.element.createElement("h1", null, "first three products");
  },
});
