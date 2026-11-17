# Versionamento do GymProgress

O GymProgress está em **pré-lançamento privado**. A versão atual é mantida em [`VERSION`](../VERSION), e o histórico de alterações efetivas em [`CHANGELOG.md`](../CHANGELOG.md).

## Formato

Usamos uma adaptação do versionamento semântico: `MAJOR.MINOR.PATCH-pré-lançamento.N`.

- **Alpha:** novas funcionalidades e alterações de UI durante desenvolvimento; compatibilidade sujeita a mudanças.
- **Beta:** conjunto de funcionalidades consolidado, foco em testes, consistência, backup e segurança.
- **RC:** candidato a versão estável; mudanças limitadas a correções e validação.
- **Estável:** versão `1.0.0` após validação final para uso pessoal.

Uma tag/release do GitHub deve ser publicada **somente** após commit, testes e validação do marco; não declarar tags publicadas que não existam.

## Regras para novas versões

1. Concluir a implementação e atualizar a documentação específica.
2. Executar e registrar testes; pendências permanecem visíveis.
3. Atualizar o README, inclusive a íntegra das RN/CT/RF/RNF quando alteradas e aprovadas.
4. Registrar em `CHANGELOG.md` as novidades e correções **reais**.
5. Atualizar `VERSION` e o cabeçalho do README no mesmo commit de fechamento do marco.
6. Não alterar número de versão apenas por sugestão de roadmap.

**Marcos planejados** ficam no README e não implicam alterações no arquivo `VERSION`.
