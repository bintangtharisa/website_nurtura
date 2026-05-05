from flask import Flask, request, jsonify
import joblib
import numpy as np

app = Flask(__name__)

bundle = joblib.load("model.pkl")

if not isinstance(bundle, dict) or "model" not in bundle:
    raise Exception("model.pkl bukan bundle yang valid")

model = bundle["model"]

@app.route("/", methods=["GET"])
def home():
    return jsonify({
        "status": True,
        "message": "ML API Running"
    })

@app.route("/predict", methods=["POST"])
def predict():
    try:
        data = request.get_json()

        if not data or "features" not in data:
            return jsonify({
                "status": False,
                "message": "Features tidak ditemukan"
            }), 400

        features = data["features"]

        if not isinstance(features, list):
            return jsonify({
                "status": False,
                "message": "Format features harus array"
            }), 400

        try:
            features = [float(x) for x in features]
        except:
            return jsonify({
                "status": False,
                "message": "Features harus angka"
            }), 400

        X = np.array([features])

        prediction = model.predict(X)[0]

        confidence = None
        if hasattr(model, "predict_proba"):
            proba = model.predict_proba(X)[0]
            confidence = float(proba[1])

        return jsonify({
            "status": True,
            "result": "Ya" if prediction == 1 else "Tidak",
            "confidence": confidence
        })

    except Exception as e:
        return jsonify({
            "status": False,
            "message": "Gagal prediksi",
            "error": str(e)
        }), 500

if __name__ == "__main__":
    app.run(debug=True)