<?php

/**
*
*/
class ChartDown_Chart_Note
{
    protected $note;
    protected $letter;
    protected $accidental;

    public function __construct($note)
    {
        $this->parseNote($note);
    }

    public function toString()
    {
        return (string) $this->getNote() . $this->getAccidental();
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setNote($note)
    {
        $this->parseNote($note);
    }

    public function getLetter()
    {
        return $this->letter;
    }

    public function getAccidental()
    {
        return $this->accidental;
    }

    protected function parseNote($note)
    {
        if (!preg_match('/[A-G1-7]/', strtoupper($note[0]))) {
          throw new InvalidArgumentException(sprintf("'%s' is not a valid note", $note));
        }

        $this->letter = $note[0];

        if (strlen($note) == 2) {
            // Accidental
            if ($note[1] && !preg_match('/[b#]/', $note[1])) {
              throw new InvalidArgumentException(sprintf("'%s' is not a valid accidental", $note[1]));
            }

            $this->accidental = $note[1] == 'b' ? ChartDown::FLAT : ChartDown::SHARP;
        } elseif (strlen($note) > 2) {
            throw new InvalidArgumentException(sprintf("Cannot parse '%s' as part of a note", substr($note, 2)));
        } else {
            $this->accidental = null;
        }

        $this->note = $note;
    }
}
