# Music School API

Esta é uma API para gerenciar cursos em uma escola de música. A API oferece operações CRUD (Criar, Ler, Atualizar) para gerenciar os cursos oferecidos pela escola.

## Endpoints da API

### 1. Obter todos os cursos ativos

**Endpoint**: `GET /api/courses`

**Descrição**: Retorna a lista de cursos ativos.

**Resposta**:
- **Código 200**: Lista de cursos ativos.

**Exemplo de resposta**:
```json
[
    {
        "id": 1,
        "name": "Curso de Guitarra",
        "description": "Curso para iniciantes",
        "status": true
    },
    {
        "id": 2,
        "name": "Curso de Piano",
        "description": "Curso para iniciantes de piano",
        "status": true
    }
]
