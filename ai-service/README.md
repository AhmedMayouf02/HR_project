# ai-service

Python (FastAPI) service for **your own models**: training them and serving
predictions. Only the Laravel backend calls it; the frontend never does.

AI features that just call an external AI provider's API do **not** belong
here. They live in `backend/app/AI/`.

```
ai-service/
├── app/
│   ├── main.py        ← FastAPI app, registers routers
│   ├── config.py      ← settings from .env
│   ├── api/           ← HTTP endpoints (health, predictions)
│   └── ml/            ← inference: load a model from artifacts/, predict
├── training/          ← scripts that train models and save them to artifacts/
├── data/              ← datasets (git-ignored)
├── artifacts/         ← trained model files (git-ignored)
└── tests/
```

## Run

```bash
cd ai-service
python3 -m venv .venv
.venv/bin/pip install -r requirements.txt
cp .env.example .env
.venv/bin/uvicorn app.main:app --reload --port 8001
```

Tests: `.venv/bin/pytest`

## Security

Set the same secret in `ai-service/.env` (`SERVICE_TOKEN`) and
`backend/.env` (`AI_SERVICE_TOKEN`). Laravel sends it as `X-Service-Token`.
Employee data stays inside your infrastructure.
