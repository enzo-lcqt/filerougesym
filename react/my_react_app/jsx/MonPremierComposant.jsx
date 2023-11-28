import React, { useEffect, useState } from "react";
import { Card, Spinner } from "react-bootstrap";

const MonPremierComposant = (props) => {
    // État de l'animation du chargement des données
    const [loading, setLoading] = useState(true);
    const [categories, setCategories] = useState([]);

    // Fonction pour récupérer les catégories de votre base de données
    const fetchCategories = async () => {
        try {
            // Remplacez l'URL par celle de votre API pour récupérer les catégories
            const response = await fetch("https://127.0.0.1:8000/api/categories?page=1");
            const data = await response.json();
            setCategories(data['hydra:member']);
            setLoading(false);
        } catch (error) {
            console.error("Erreur lors de la récupération des catégories", error);
        }
    };

    // Appeler la fonction fetchCategories lors du montage du composant
    useEffect(() => {
        fetchCategories();
    }, []);

    return (
        <div className={"container"}>
            {loading ? (
                <Spinner animation="grow" variant="info" />
            ) : (
                <>
                    <div className="row">
                        <div className={'col-12 mt-4'}>
                            <h1 className={'p-3 text-center'} style={{ color: "#4169E1", fontSize: "2.8em" }}>Catégories</h1>
                            <hr />
                        </div>
                    </div>
                    <div className="row">
                        {categories.map((category, index) => (
                            <div className="col-md-4 p-2" key={index}>
                                <Card style={{ width: '18rem' }}>
                                    <Card.Img variant="top" src={category.image} />
                                    <Card.Body>
                                        <Card.Title>{category.libelle}</Card.Title>
                                        <Card.Text>
                                            {category.description}
                                        </Card.Text>
                                    </Card.Body>
                                </Card>
                            </div>
                        ))}
                    </div>
                </>
            )}
        </div>
    );
};

export default MonPremierComposant;
