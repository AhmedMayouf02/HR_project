# app/ml

Inference code: load a trained model from `artifacts/` and run predictions.
One module per model, e.g. `attrition.py` exposing `predict(features) -> result`.
The API routers in `app/api/` call these; they never train.
