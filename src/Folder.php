<?php
namespace Lazysearch;

class Folder
{
    /** @var Client $client */
    protected $client;

    protected $name;

    public function __construct(Client $client, string $name)
    {
        $this->client = $client;
        $this->name = $name;
    }

    public function put(string $id, array $data)
    {
        return $this->client->_request('PUT', "/{$this->name}/{$id}", null, $data);
    }

    /**
     * searches this folder for $query with $options.
     *
     * @param array $options configure the search with the following options:
     * * queryType - leave empty for standard query.
     *   Other options are: 'prefix', 'phrase' and 'advanced'.
     * * field - which field in the documents to search?
     *  default is '*' (all fields).
     * * fields - which fields from the documents to return?
     *   default is '*' (all fields).
     * * sort - how to sort the results? default is by _score descending (most relevant first)
     *   Examples:
     *     'age' sorts by field 'age' ascending
     *     '-age' sorts by field 'age' descending (reverse order)
     *     '-age,register_date' sorts by field 'age' descending,
     *       then by field 'register_date'
     * * typos - allow a specified number of typos in the search query.
     *   default is 0 (no typos allowed)
     * * highlight - highlight matched words with HTML <mark> tags. default is false
     * * page - default is 1
     * * hitsPerPage - default is 10
     * @return array
     * @throws LazysearchException
     **/
    public function search(string $query, array $options = [])
    {
        $options = array_merge(Client::$defaultOptions, $options);
        $params = [
            'q' => $query,
            't' => $options['queryType'] ?? '',
            'field' => $options['field'] ?? '*',
            'fields' => $options['fields'] ?? '*',
            'sort' => $options['sort'] ?? '-_score',
            'typos' => $options['typos'] ?? 0,
            'p' => $options['page'] ?? 1,
            'pp' => $options['hitsPerPage'] ?? 10
        ];
        if(isset($options['highlight'])) {
            $params['hl'] = true;
        }
        return $this->client->_request('GET', "/{$this->name}/_search", $params);
    }
}