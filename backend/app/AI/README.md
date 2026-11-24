# app/AI

All AI features of the backend live here.

```
AI/
├── Clients/     ← one class per AI backend
│   └── AiServiceClient.php   (our Python service: ../../../ai-service)
│                (an external LLM API client goes here once a provider is chosen)
├── Features/    ← one class per feature, e.g. CvScreening, PolicyAssistant,
│                  AttritionRisk. Uses a client + Eloquent models.
└── Prompts/     ← prompt templates for LLM features (kept out of PHP logic)
```

Rules:
- Controllers call a `Features/` class, never a client directly.
- Slow AI calls run in a queued job, not in the request.
- Only send the employee fields a feature actually needs.
- Which backend a feature uses:
  - general language tasks (summarise, draft, answer, extract) → external LLM API
  - predictions from your own HR data (attrition, salary bands…) → `AiServiceClient`
